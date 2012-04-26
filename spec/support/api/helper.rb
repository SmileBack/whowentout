module ApiHelper
  include Rack::Test::Methods

  def app
    Rails.application
  end

  def get_json(url, options = {}, session = {})
    get url, options, 'rack.session' => sign_in_parameters.merge(session)
    JSON.parse(last_response.body)
  end

  def post_json(url, options = {}, session = {})
    post url, options, 'rack.session' => sign_in_parameters.merge(session)
    JSON.parse(last_response.body)
  end

  def sign_in
    @signed_in = true
  end

  def sign_out
    @signed_in = false
  end

  def sign_in_parameters
    if @signed_in == true
      {
        'user_id' => venkats_id,
        'token' => venkats_token
      }
    else
      {}
    end
  end

  def venkats_id
    User.find_by_first_name_and_last_name('Venkat', 'Dinavahi').id
  end
  def venkats_token
    "AAACEdEose0cBAM04sZBwqPbKm1SviCmFSA9vewlGxZBFadbXZCCNCwY4ZBnHtd4l62d3vv7V2PyXkP1LwHblNE8AwFC0ZCwnTFVhtphtXMwZDZD"
  end

  def dans_id
    User.find_by_first_name_and_last_name('Dan', 'Berenholtz').id
  end
  def dans_token
    "AAACEdEose0cBAD2RuX8t5pcS9km8AHpZBQ6Ah3DA2IGJUw6zXwVZCIbj8WKRsNqJQPsBvmqRCw7YSUYIdO0uyvvgDsK3bExUQZC807BHgZDZD"
  end

end


