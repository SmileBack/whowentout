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
    "AAACEdEose0cBACKmYiSH4HgiZA4WxhA6CUPVZC7xJbyt2SWgjp7O0D9j9Wbb1v4ZC0TOAmuZBzO7pXxPFp8FcZCwG8JtDjUydzGeVUDZCdhAZDZD"
  end

  def dans_id
    User.find_by_first_name_and_last_name('Dan', 'Berenholtz').id
  end
  def dans_token
    "AAACEdEose0cBAAZBUmSRx9hZAXvM2C9MAMkmXeUPyrqRIPdqWf9m1QUoDCbIKaRJ2cw9FeShnpjeVylajHZA2UCDdSgT2f1LPwQ2MVw1gZDZD"
  end

end


