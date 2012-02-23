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
    end
  end

end
