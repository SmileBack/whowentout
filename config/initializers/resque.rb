unless ENV["REDISTOGO_URL"].nil?
  uri = URI.parse(ENV["REDISTOGO_URL"])
  Resque.redis = Redis.new(:host => uri.host, :port => uri.port, :password => uri.password)

  Resque.before_fork = Proc.new { ActiveRecord::Base.establish_connection }
end



