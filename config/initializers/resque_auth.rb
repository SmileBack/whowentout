require 'resque/server'

Resque::Server.use(Rack::Auth::Basic) do |user, password|
  user == 'admin' && password == "password"
end
