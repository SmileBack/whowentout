require 'sinatra'
require 'json'

require_relative 'lib/facebook_linker'

linker = FacebookLinker.new

get '/link' do
  student = linker.cross_link_user(params[:network], params[:name], params[:facebook_id])

  response = {:name => params[:name], :facebook_id => params[:facebook_id]}

  unless student.nil?
    response[:email] = student.email
  end

  response.to_json
end
