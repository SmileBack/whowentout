require 'spec_helper'
require 'rake'

describe Region do

  let(:nyc_points) do
    [[40.7016, -74.0133], [40.7093, -73.9957], [40.7132, -73.9778], [40.7210, -74.0123]]
  end

  let(:dc_points) do
    [[38.995, -77.040], [38.893, -76.913], [38.813, -77.018], [38.871, -77.021], [38.934, -77.116]]
  end

  let(:nyc) do
    Region.create!(:name => 'nyc', :points => nyc_points)
  end

  let(:dc) do
    Region.create!(:name => 'dc', :points => dc_points)
  end

  before(:all) do
    nyc
    dc
  end

  it "should update the bounding box after a save" do
    nyc.lat_min.should == 40.7016
    nyc.lat_max.should == 40.7210
    nyc.lng_min.should == -74.0133
    nyc.lng_max.should == -73.9778
  end

  describe "include?" do
    it "should contain a point inside its boundaries" do
      nyc.include?([40.71265, -73.99843]).should be_true
    end

    it "shouldnt contain a point inside its bounding box BUT outside its boundaries" do
      nyc.include?([40.7039, -74.0038]).should be_false
      nyc.include?([40.7112, -74.0191]).should be_false
    end

    it "shouldnt contain a point outside its bounding box" do
      nyc.include?([40.7130, -73.9603]).should be_false
    end
  end

  describe "including" do
    it "should only return a region that contains a point" do
      Region.including([38.921, -77.014]).length.should == 1
      Region.including([38.921, -77.014]).first.should == dc

      Region.including([40.71265, -73.99843]).length.should == 1
      Region.including([40.71265, -73.99843]).first == nyc
    end

    it "should return no regions when the none of the regions contain the point" do
      Region.including([40.162, -82.903]).length.should == 0
    end

    it "should be a relation" do
      Region.including([40.71265, -73.99843]).where(:name => 'nyc').length.should == 1
      Region.including([40.71265, -73.99843]).where(:name => 'nyc').first == nyc

      Region.where(:name => 'nyc').including([40.71265, -73.99843]).first.should == nyc
    end

    it "should return all overlapping regions" do

    end

  end

end
