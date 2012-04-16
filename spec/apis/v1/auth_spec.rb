require 'spec_helper'

describe "/auth", :type => :api do

  let(:token) { "AAACm1V7H288BAEsdcHrcsMGDmjqTKLchLIFHu2Jh0HeZA0XZBvyuyZCvOGEFr9mMp4oiMFr2Yw6Nq1leTf9FasRZBKKtXUMZD" }

  it "should start off as logged out" do
    response = get_json('api/v1/auth/current-user.json')
    response['success'].should == false
  end

  describe "/login" do
    it "should work with a valid token" do
      post "api/v1/auth/login.json", :token => token

      login_response = post_json 'api/v1/auth/login.json', :token => token
      login_response['success'].should == true

      response = get_json("api/v1/auth/current-user.json")
      response['first_name'].should == 'Venkat'
    end
  end

  describe "/logout" do
    it "should affect current-user" do
      response = post_json('api/v1/auth/logout.json')
      response['success'].should == true

      response = get_json('api/v1/auth/current-user.json')
      response['success'].should == false
    end
  end

end