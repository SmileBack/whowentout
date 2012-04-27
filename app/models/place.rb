class Place < ActiveRecord::Base
  geocoded_by :address
  after_validation :geocode

  validates_presence_of :name

  acts_as_taggable

  serialize :details, JSONColumn.new
end

