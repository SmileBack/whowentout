require 'rubygems'
require 'fb_graph'

require_relative 'db_hash'

class FacebookIdDirectory

  def initialize
    @ids = DbHash.new 'data/facebook_ids.db'
  end

  def [](username)
    if not @ids.has_key?(username)
      facebook_user = lookup_facebook_user(username)

      if facebook_user.nil?
        @ids[username] = nil
      else
        @ids[username] = facebook_user.identifier
      end
    end

    return @ids[username]
  end

  def lookup_facebook_user(facebook_id)
    begin
      FbGraph::User.fetch(facebook_id)
    rescue FbGraph::NotFound => e
      puts "#{facebook_id} not found"
    rescue FbGraph::Unauthorized => e
      puts "#{facebook_id} unauthorized"
    rescue FbGraph::Exception => e
      puts "#{facebook_id} facebook graph error"
    rescue URI::InvalidURIError => e
      puts "#{facebook_id} invalid uri"
    end
  end

end
