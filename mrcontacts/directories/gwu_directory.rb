require 'rubygems'
require 'mechanize'
require 'yaml'

class GWUDirectory
  include EventPublisher

  event :on_login
  event :on_search
  event :on_load_page

  def initialize
    @agent = Mechanize.new
  end

  def search(keywords)
    students = []

    # goto directory page
    @agent.get(base_url)

    search_form = @agent.page.form_with :action => 'index.cfm'
    search_form['keywords'] = keywords
    search_form.field_with(:name => 'role').value = 'Student'

    @agent.submit search_form

    students += extract_students_from_page(@agent.page)
    links = extract_paging_links(@agent.page)
    @num_reported_results = extract_num_results(@agent.page)
    @num_pages = links.count + 1

    trigger :on_search, keywords, num_reported_results, links.count

    links.each do |a|
      @agent.get( base_url + a['href'] )
      page_name = a.text.gsub /(\s|\n)+/, ''
      students += extract_students_from_page(@agent.page)
      trigger :on_load_page, keywords, page_name
    end

    return students
  end

  def login(username, password)
    #login
    @agent.get(login_url)

    login_form = @agent.page.form_with :action => 'validate.cfm'

    login_form['username'] = username
    login_form['password'] = password

    @agent.submit login_form

    trigger :on_login, username, password
  end

  def num_reported_results
    @num_reported_results
  end

  def num_pages
    @num_pages
  end

  def base_url
    'http://my.gwu.edu/mod/directory/'
  end

  def login_url
    'https://my.gwu.edu/login/'
  end

  private

  def extract_num_results(page)
    doc = page.root

    return 0 if has_no_matches(doc)

    normal = doc.at_xpath('//text()[contains(., "resulted in a total of")]')
    if ! normal.nil?
      return normal.parent.content.scan(/\d+/).first.to_i
    end

    over = doc.at_xpath('//text()[contains(., "resulted in more than")]')
    if ! over.nil?
      return -1 * over.parent.content.scan(/\d+/).first.to_i
    end
  end

  def has_no_matches(doc)
    div = doc.at_xpath("//div[@class='alertMsg']")
    return false if div.nil?
    return div.content.include?('returned no matches')
  end

  def extract_students_from_page(page)
    links = page.search("//a[starts-with(@href,'mailto')]")

    students = []

    links.each do |a|
      s = {}
      s[:name] =  a.at_xpath("ancestor::td[1]//b[1]").text.strip
      s[:email] = a.text.strip

      students << s
    end

    return students
  end

  def extract_paging_links(page)
    paging_links = []

    links = page.search("//a[starts-with(@href,'index.cfm')]")
    links.each do |a|
      paging_links << a
    end

    return paging_links
  end

end
