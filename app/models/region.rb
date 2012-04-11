class Region < ActiveRecord::Base
  before_save :update_bounding_box

  validates :name, :presence => true

  serialize :points, Array

  # Reduces the set to regions that contain the point
  def self.including(point)
    region_ids = select { |region| region.include?(point) }.pluck(:id)
    where('lat_min <= ?', point[0]) \
      .where('lat_max >= ?', point[0]) \
      .where('lng_min <= ?', point[1])
      .where('lng_max >= ?', point[1])
      .where(:id => region_ids)
  end

  # [latitude, longitude] (equivalent to y, x in cartesian coordinates)
  #   point[1] is the x coordinate
  #   point[0] is the y coordinate
  def include?(point)
    return false if outside_bounding_box?(point)

    contains_point = false
    j = points.size - 1
    points.each_index do |i|
      point_on_polygon = points[i]
      trailing_point_on_polygon = points[j]
      if point_is_between_the_ys_of_the_line_segment?(point, point_on_polygon, trailing_point_on_polygon) \
         && ray_crosses_through_line_segment?(point, point_on_polygon, trailing_point_on_polygon)
          contains_point = !contains_point
      end
      j = i
    end

    return contains_point
  end

  private

  def update_bounding_box
    self.lat_min = points.pluck(:first).min
    self.lat_max = points.pluck(:first).max
    self.lng_min = points.pluck(:second).min
    self.lng_max = points.pluck(:second).max
  end

  def outside_bounding_box?(point)
    min_x, max_x = lng_min, lng_max
    min_y, max_y = lat_min, lat_max

    point[1] < min_x || point[1] > max_x || point[0] < min_y || point[0] > max_y
  end

  def point_is_between_the_ys_of_the_line_segment?(point, point_on_polygon, trailing_point_on_polygon)
    (point_on_polygon[0] <= point[0] && point[0] < trailing_point_on_polygon[0]) ||
    (trailing_point_on_polygon[0] <= point[0] && point[0] < point_on_polygon[0])
  end

  def ray_crosses_through_line_segment?(point, point_on_polygon, trailing_point_on_polygon)
    (point[1] < (trailing_point_on_polygon[1] - point_on_polygon[1]) * (point[0] - point_on_polygon[0]) /
               (trailing_point_on_polygon[0] - point_on_polygon[0]) + point_on_polygon[1])
  end

end
