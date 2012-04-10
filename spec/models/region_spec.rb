require 'spec_helper'

describe Region do

  let(:nyc_points) do
    [[40.7016, -74.0133],
     [40.7093, -73.9957],
     [40.7132, -73.9778],
     [40.7210, -74.0123]]
  end

  before do
    @nyc = Region.create!(:name => 'nyc', :points => nyc_points)
  end

  it "should update the bounding box after a save" do
    @nyc.lat_min.should == 40.7016
    @nyc.lat_max.should == 40.7210
    @nyc.lng_min.should == -74.0133
    @nyc.lng_max.should == -73.9778
  end

  it "should contain a point inside its boundaries" do
    @nyc.include?([40.71265, -73.99843]).should be_true
  end

  it "shouldnt contain a point inside its bounding box BUT outside its boundaries" do
    @nyc.include?([40.7039, -74.0038]).should be_false
    @nyc.include?([40.7112, -74.0191]).should be_false
  end

  it "shouldnt contain a point outside its bounding box" do
    @nyc.include?([40.7130, -73.9603]).should be_false
  end

end
