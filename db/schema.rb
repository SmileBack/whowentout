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

ActiveRecord::Schema.define(:version => 20120423184527) do

  create_table "facebook_friendships", :force => true do |t|
    t.integer "user_id",   :null => false
    t.integer "friend_id", :null => false
  end

  add_index "facebook_friendships", ["user_id", "friend_id"], :name => "index_facebook_friendships_on_user_id_and_friend_id"

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

  create_table "regions", :force => true do |t|
    t.string  "name"
    t.text    "points"
    t.text    "text"
    t.decimal "lat_min", :precision => 15, :scale => 10
    t.decimal "lat_max", :precision => 15, :scale => 10
    t.decimal "lng_min", :precision => 15, :scale => 10
    t.decimal "lng_max", :precision => 15, :scale => 10
  end

  create_table "users", :force => true do |t|
    t.integer  "facebook_id",    :limit => 8
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
    t.boolean  "is_active",                   :default => false
  end

end
