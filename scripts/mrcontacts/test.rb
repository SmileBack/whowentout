require 'sinatra'
require 'json'

require_relative 'lib/facebook_linker'

linker = FacebookLinker.new

get '/link' do
  student = linker.cross_link_user(params[:name], params[:facebook_id])
  response = {:name => params[:name], :facebook_id => params[:facebook_id], :email => student.email}

  response.to_json
end
