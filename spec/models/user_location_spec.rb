require 'spec_helper'

describe User do
  describe "location" do

    it "should exist" do
      venkat = create(:user, first_name: "Venkat", last_name: "Dinavahi")
      venkat.should respond_to(:location)
    end

    it "should start out nil" do
      venkat = create(:user, first_name: "Venkat", last_name: "Dinavahi")
      venkat.location.should == nil
    end

  end
end