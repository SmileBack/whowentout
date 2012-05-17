require 'yelp/yelp_search'

class YelpSyncer

  attr_accessor :log

  def initialize
    self.log = Logger.new(nil)
  end

  def sync(results_url)
    search = YelpSearch.new(results_url)

    search.each_result do |result|
      place = Place.find_by_address(result.address)

      if place.nil?
        place = Place.new
        action = "create"
      else
        action = "update"
      end

      place.name = result.name
      place.address = result.address
      place.phone_number = result.phone_number
      place.tag_list = result.categories.join(', ')
      place.save

      log.info "Result #{result.num}: #{action} #{place.name} ..."
    end

  end


end
