class Region < ActiveRecord::Base
  before_save :update_bounding_box

  validates :name, :presence => true

  serialize :points, Array

  private

  def update_bounding_box
    self.lat_max = 53.23
    self.lat_min = 50.55
    self.lng_max = 20.75
    self.lng_min = 88.35
  end

end
