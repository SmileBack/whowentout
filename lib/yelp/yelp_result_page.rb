require 'mechanize'
require 'yelp/yelp_place'

class YelpResultPage

  attr_accessor :log

  def initialize(url)
    @agent = Mechanize.new
    @url = url

    self.log = Logger.new(nil)
  end

  def results
    page.root.css('.businessresult').map do |result|
      YelpPlace.new(result)
    end
  end

  def next
    YelpResultPage.new(next_link) if has_next?
  end

  def has_next?
    not next_link.nil?
  end

  def results_start
    page.root.at_css('.pager_start').text.strip.to_i
  end

  def results_end
    page.root.at_css('.pager_end').text.strip.to_i
  end

  def results_total
    page.root.at_css('.pager_total').text.strip.to_i
  end

  private

  def next_link
    href(id: 'pager_page_next')
  end

  def href(attributes)
    link = page.link_with(attributes)
    base_url(link.attributes['href']) unless link.nil?
  end

  def page
    if @page.nil?
      @page = @agent.get(@url)
      log.info "Fetched results #{results_start} - #{results_end} of #{results_total}"
    end
    @page
  end

  def base_url(path = '')
    uri = page.uri
    "#{page.uri.scheme}://#{page.uri.host}#{path}"
  end

end
