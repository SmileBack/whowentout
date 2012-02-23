require 'rubygems'
require 'mechanize'
require 'cgi'
require 'uri'

class GeorgetownDirectory
  include EventPublisher

  event :on_login
  event :on_search
  event :on_load_page

  def initialize
    @agent = Mechanize.new
  end

  def search(keywords)
    students = []

    page = load_search_result_page(keywords)
    doc = page.root

    @num_reported_results = extract_num_results(page)

    rows = doc.xpath('//tr[@class="ListPrimary" or @class="ListAlternate"]').to_a
    trigger :on_search, keywords, num_reported_results, 1

    rows.each do |row|
      student = {}

      link_to_student = row.at_xpath('.//td[1]/a')
      role_td = row.at_xpath('.//td[2]')

      student[:name] = link_to_student.text.strip
      student[:link] = base_url + link_to_student['href']
      student[:role] = role_td.text.gsub(/(\s|\n)+/, ' ').strip
      student[:email] = fetch_email( student[:link] )

      students << student
    end

    return students
  end

  def num_reported_results
    @num_reported_results
  end

  private

  def fetch_email(student_link)
    params = CGI.parse(URI.parse(student_link).query)
    return params["NetID"].first + '@georgetown.edu'
  end

  def load_search_result_page(keywords)
    @agent.get(search_url)

    search_form = @agent.page.form_with :action => search_action

    search_form['FirstNameMatch']= 'starts'
    search_form.checkbox_with(:name => 'SearchAffiliation', :value => 'Employee').uncheck

    search_form['FirstName'] = keywords

    @agent.submit search_form

    trigger :on_load_page, keywords, '[main]'

    return @agent.page
  end

  def base_url
    'http://contact.georgetown.edu/'
  end

  def search_url
    base_url + 'index.cfm?Action=HomeAdvanced'
  end

  def search_action
    'index.cfm?Action=SearchResults&RequestTimeout=30'
  end

  def has_no_matches(page)
    try_again_link = page.link_with :text => /try again/
    return !try_again_link.nil?
  end

  def extract_num_results(page)
    doc = page.root

    return 0 if has_no_matches(page)

    normal = page.root.at_xpath('//text()[contains(., "directory entries match your search")]')
    if ! normal.nil?
      count = normal.content.scan(/\d+/).first.to_i
      count *= -1 if count == 500
      return count
    end
  end

end

