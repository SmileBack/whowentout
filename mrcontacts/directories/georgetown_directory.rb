require 'rubygems'
require 'mechanize'
require 'yaml'

class GeorgetownDirectory

  def search(query)
    students = []
    doc = load_doc(query)
    rows = doc.xpath('//tr[@class="ListPrimary" or @class="ListAlternate"]').to_a

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

  private

  def fetch_email(student_link)
    agent = Mechanize.new
    agent.get(student_link)

    link = agent.page.link_with :href => /^mailto:/

    return link.nil? ? nil : link.text.strip
  end

  def load_doc(query)
    agent = Mechanize.new
    agent.get(search_url)

    search_form = agent.page.form_with :action => search_action

    search_form['FirstNameMatch']= 'starts'
    search_form.checkbox_with(:name => 'SearchAffiliation', :value => 'Employee').uncheck

    search_form['FirstName'] = query

    agent.submit search_form

    doc = agent.page.root

    return doc
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

end

