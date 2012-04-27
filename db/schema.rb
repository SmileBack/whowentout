# encoding: UTF-8
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended to check this file into your version control system.

ActiveRecord::Schema.define(:version => 20120427193622) do

  create_table "facebook_friendships", :force => true do |t|
    t.integer "user_id",   :null => false
    t.integer "friend_id", :null => false
  end

  add_index "facebook_friendships", ["user_id", "friend_id"], :name => "index_facebook_friendships_on_user_id_and_friend_id", :unique => true

  create_table "interests", :force => true do |t|
    t.integer "facebook_id", :limit => 8
    t.string  "name",                     :null => false
  end

  add_index "interests", ["facebook_id"], :name => "index_interests_on_facebook_id"

  create_table "network_memberships", :force => true do |t|
    t.integer "user_id"
    t.integer "network_id"
  end

  add_index "network_memberships", ["user_id", "network_id"], :name => "index_network_memberships_on_user_id_and_network_id", :unique => true

  create_table "networks", :force => true do |t|
    t.integer "facebook_id",  :limit => 8
    t.string  "network_type",              :null => false
    t.string  "name",                      :null => false
  end

  add_index "networks", ["facebook_id"], :name => "index_networks_on_facebook_id", :unique => true
  add_index "networks", ["network_type"], :name => "index_networks_on_type"

  create_table "photos", :force => true do |t|
    t.integer "user_id"
    t.string  "facebook_id"
    t.time    "created_at"
    t.text    "thumb"
    t.text    "large"
  end

  add_index "photos", ["facebook_id"], :name => "index_photos_on_facebook_id"
  add_index "photos", ["user_id"], :name => "index_photos_on_user_id"

  create_table "places", :force => true do |t|
    t.string "type"
    t.string "name"
    t.string "phone_number"
    t.string "address"
    t.float  "latitude",     :null => false
    t.float  "longitude",    :null => false
    t.text   "details"
  end

  create_table "regions", :force => true do |t|
    t.string "name"
    t.text   "points"
    t.text   "text"
    t.float  "lat_min"
    t.float  "lat_max"
    t.float  "lng_min"
    t.float  "lng_max"
  end

  create_table "user_interests", :force => true do |t|
    t.integer "user_id"
    t.integer "interest_id"
  end

  add_index "user_interests", ["user_id", "interest_id"], :name => "index_user_interests_on_user_id_and_interest_id", :unique => true

  create_table "user_locations", :force => true do |t|
    t.integer  "user_id",                       :null => false
    t.float    "latitude",                      :null => false
    t.float    "longitude",                     :null => false
    t.boolean  "is_active",  :default => false, :null => false
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "users", :force => true do |t|
    t.integer  "facebook_id",         :limit => 8
    t.string   "first_name"
    t.string   "last_name"
    t.string   "email"
    t.datetime "created_at"
    t.datetime "updated_at"
    t.string   "gender"
    t.date     "birthday"
    t.string   "hometown"
    t.string   "current_city"
    t.text     "facebook_token"
    t.boolean  "is_active",                        :default => false
    t.string   "relationship_status"
    t.string   "interested_in"
    t.string   "work"
    t.integer  "photo_id"
  end

end
