require 'mechanize'

class YelpResultPage

  def fetch
    url = 'http://www.yelp.com/search?cflt=nightlife&find_desc=&find_loc=Manhattan%2C+NY#rpp=40'
    agent = Mechanize.new

    page = agent.get(url)

    businesses = []

    page.root.css('.businessresult').each do |result|
      businesses << businessresult_result_to_hash(result)
    end

    return businesses
  end

  def businessresult_result_to_hash(result)
    {
        :name => result.css('.itemheading').text.strip.gsub(/^\d+\.\s*/, ''),
        :address => result.css('address > div:not(.phone)').inner_html.strip.gsub('<br>', ', '),
        :phone => result.css('.phone').text.strip,
        :categories => result.css('.itemcategories a').map { |c| c.text.strip }
    }
  end

end
