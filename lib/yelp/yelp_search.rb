require 'yelp/yelp_result_page'

class YelpSearch

  def initialize(url)
    @url = url
    @log = Logger.new(nil)
  end

  def log
    @log
  end
  def log=(v)
    @log = v
  end

  def each_result
    cur_page = YelpResultPage.new(@url)
    while cur_page != nil
      cur_page.results.each { |result| yield result }
      cur_page = cur_page.next
    end
  end

end
