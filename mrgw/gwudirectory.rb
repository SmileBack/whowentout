require 'open-uri'
require 'nokogiri'
require 'net/http'

class GWUDirectory
  
  def initialize
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
      return {:count => 0, :students => []}
    end
    
    if ! valid_page(doc)
      raise "invalid page"
    end
    
    doc.xpath("//a[starts-with(@href, 'mailto:')]").each do |a|
      s = {}
      s[:name] = a.at_xpath("ancestor::td[1]//b[1]").text.strip
      s[:email] = a.text.strip
      students << s
    end
    
    return {:count => @num_results, :students => students}
  end
  
  def valid_page(doc)
    return doc.xpath("//input[@name='keywords']").length > 0
  end

  def num_results
    @num_results
  end

  #private
  def log_num_results(query, num_results)
    @queries_log[query] = num_results
  end
  
  def get_num_results(doc)
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
