require 'rubygems'
require 'mechanize'

class CUADirectory
  include EventPublisher

  event :on_login
  event :on_search
  event :on_load_page #keywords, page_name

  def initialize
    @agent = Mechanize.new
  end

  def search(keywords)
    students = []

    page = load_search_result_page(keywords)

    links = extract_student_links(page)

    trigger :on_search, keywords, links.count, 1

    links.each do |a|
      student = {}
      student[:name] = a.text.strip
      student[:link] = base_url + a['href']
      student[:email] = extract_email(student)

      students << student
    end

    return students
  end

  def num_reported_results
    nil
  end

  private

  def extract_student_links(page)
    paging_links = []

    links = page.search("//a[starts-with(@href,'index.cfm?main=homedirectory&ID=')]")
    links.each do |a|
      paging_links << a
    end

    return paging_links
  end

  def extract_email(student_data)
    @agent.get(student_data[:link])

    trigger :on_load_page, "user page", student_data[:name]

    email_link = @agent.page.search("//a[starts-with(@href,'mailto')]").first
    if !email_link.nil?
      return email_link.text.strip
    else
      html = @agent.page.root.to_html
      result = html.scan(/var usrID = '([^']+)'/)

      return nil if result.empty?

      email = result[0][0] + '@cardinalmail.cua.edu'
    end

    return email
  end

  def load_search_result_page(keywords)
    @agent.get(search_url)

    search_form = @agent.page.form_with :action => search_action
    search_form['name'] = keywords

    @agent.submit search_form

    trigger :on_load_page, keywords, '[main]'

    return @agent.page
  end

  def base_url
    'https://home.cua.edu/'
  end

  def search_url
    base_url + 'index.cfm?main=homedirectory'
  end

  def search_action
    'index.cfm?main=homedirectory'
  end

  def has_no_matches(page)
    try_again_link = page.link_with :text => /try again/
    return !try_again_link.nil?
  end

end
