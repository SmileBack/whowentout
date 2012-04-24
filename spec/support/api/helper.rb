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
    User.first.id
  end

  def venkats_token
    "AAACEdEose0cBALvOM1GpXfmMctNM50gJG2IV8zJkqXvzjT3ylu1WbCbdB1PTj74oDx6eKZChuhREATXrn7rtc6FNHJDQWbRhEOcpNeAZDZD"
  end

end


