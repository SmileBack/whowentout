class YelpPlace

  def initialize(result)
    @data = result_to_data(result)
  end

  def num
    @data[:num]
  end

  def name
    @data[:name]
  end

  def address
    @data[:address]
  end

  def phone_number
    @data[:phone_number]
  end

  def categories
    @data[:categories]
  end

  private

  def result_to_data(result)
    result_title = result.css('.itemheading').text.strip
    {
        :num => result_title[ /^\d+/ ].to_i,
        :name => result_title.gsub(/^\d+\.\s*/, ''),
        :address => result.css('address > div:not(.phone)').inner_html.strip.gsub('<br>', ', '),
        :phone_number => result.css('.phone').text.strip,
        :categories => result.css('.itemcategories a').map { |c| c.text.strip }
    }
  end

end