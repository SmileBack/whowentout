require 'open-uri'
require 'nokogiri'
require 'net/http'

require './student'
require './dbhash'

class GWUDirectory
  
  def initialize
    @queries_log = DbHash.new 'data/queries.db'
  end
  
  def num_results
    @num_results.nil? ? 0 : @num_results
  end

  def search(query)
    students = []

    html = get_search_result_html(query)
    doc = Nokogiri::HTML(html)

    @num_results = get_num_results(doc)
    if @num_results == 0
      log_num_results(query, 0)
      return []
    end
    
    doc.xpath("//a[starts-with(@href, 'mailto:')]").each do |a|
      s = Student.new
      s.name = a.at_xpath("ancestor::td[1]//b[1]").text.strip
      s.email = a.text
      students << s
    end
    
    log_num_results(query, num_results)
    
    return students
  end

  def num_results
    @num_results
  end

  #private
  def log_num_results(query, num_results)
    @queries_log[query] = num_results
  end
  
  def get_num_results(doc)
    div = doc.at_xpath("//div[text()=' resulted in a total of ']")
    return 0 if div.nil?
    return div.at_xpath("//b[2]").text.to_i
  end
  

  def link_prefix
    'http://my.gwu.edu/'
  end
  def search_link(query)
    link_prefix + 'mod/directory/index.cfm' + "?keywords=#{query}&searchtype=people&role=Student"
  end
  def get_search_result_html(query)
    uri = URI.parse(search_link query)
    x = Net::HTTP.get_response(uri)
    return x.body
  end
end
