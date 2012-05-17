namespace :yelp do

  task :sync => :environment do
    link = ENV['link']
    raise "Require setting link={your link}" if ENV['link'].nil?

    require 'yelp/yelp_syncer'

    syncer = YelpSyncer.new
    syncer.log = Logger.new(STDOUT)
    syncer.sync(link)
  end

  task :syncall => :environment do
    require 'yelp/yelp_syncer'

    syncer = YelpSyncer.new
    syncer.log = Logger.new(STDOUT)

    File.open(File.join(Rails.root, 'data/yelp_links.txt')).each_line do |link|
      syncer.log.info "Syncing #{link}:"
      syncer.sync(link)
    end
  end

end
