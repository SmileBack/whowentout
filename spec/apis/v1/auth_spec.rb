require 'spec_helper'

describe "/auth", :type => :api do

  before(:all) do
    user = User.find_by_token(venkats_token)
  end

  describe "/me" do
    it "should be nil when user is logged out" do
      response = get_json('api/v1/me.json')
      response['user'].should == nil
    end

    it "should return the user when he is logged in" do
      sign_in
      response = get_json('api/v1/me.json')
      response['user']['first_name'].should == 'Venkat'

      sign_out
      response = get_json('api/v1/me.json')
      response['user'].should == nil
    end

  end

  describe "/login" do

    it "should fail with no access token" do
      response = get_json('api/v1/login.json')

      last_response.status.should == 401
      response['user'].should == nil
    end

    it "should fail with an invalid access token" do
      response = get_json('api/v1/login.json', :token => 'woohahayeah')

      last_response.status.should == 401
      response['user'].should == nil
    end

    it "should return the current user with the right access token" do
      response = get_json('api/v1/login.json', :token => venkats_token)

      last_response.status.should == 200
      response['user']['first_name'].should == 'Venkat'
    end

    it "should set the user_id" do
      response = get_json('api/v1/login.json', :token => venkats_token)

      last_response.status.should == 200
      last_request.env['rack.session'][:user_id].should == venkats_id
    end

  end

  describe "/logout" do
    it "should erase the user_id property" do
      response = get_json 'api/v1/logout.json'
      last_request.env['rack.session'][:user_id].should == nil
    end
  end

end