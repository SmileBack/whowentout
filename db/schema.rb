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

ActiveRecord::Schema.define(:version => 20120703210553) do

  create_table "active_admin_comments", :force => true do |t|
    t.string   "resource_id",   :null => false
    t.string   "resource_type", :null => false
    t.integer  "author_id"
    t.string   "author_type"
    t.text     "body"
    t.datetime "created_at"
    t.datetime "updated_at"
    t.string   "namespace"
  end

  add_index "active_admin_comments", ["author_type", "author_id"], :name => "index_active_admin_comments_on_author_type_and_author_id"
  add_index "active_admin_comments", ["namespace"], :name => "index_active_admin_comments_on_namespace"
  add_index "active_admin_comments", ["resource_type", "resource_id"], :name => "index_admin_notes_on_resource_type_and_resource_id"

  create_table "admin_users", :force => true do |t|
    t.string   "email",                  :default => "", :null => false
    t.string   "encrypted_password",     :default => "", :null => false
    t.string   "reset_password_token"
    t.datetime "reset_password_sent_at"
    t.datetime "remember_created_at"
    t.integer  "sign_in_count",          :default => 0
    t.datetime "current_sign_in_at"
    t.datetime "last_sign_in_at"
    t.string   "current_sign_in_ip"
    t.string   "last_sign_in_ip"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  add_index "admin_users", ["email"], :name => "index_admin_users_on_email", :unique => true
  add_index "admin_users", ["reset_password_token"], :name => "index_admin_users_on_reset_password_token", :unique => true

  create_table "checkins", :force => true do |t|
    t.integer  "user_id",                       :null => false
    t.integer  "place_id",                      :null => false
    t.boolean  "is_active",  :default => false, :null => false
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "facebook_friendships", :force => true do |t|
    t.integer "user_id",   :null => false
    t.integer "friend_id", :null => false
  end

  add_index "facebook_friendships", ["user_id", "friend_id"], :name => "index_facebook_friendships_on_user_id_and_friend_id", :unique => true

  create_table "friendships", :force => true do |t|
    t.string   "status"
    t.integer  "user_id"
    t.integer  "friend_id"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  add_index "friendships", ["status"], :name => "index_friendships_on_status"
  add_index "friendships", ["user_id", "friend_id"], :name => "index_friendships_on_user_id_and_friend_id", :unique => true

  create_table "interests", :force => true do |t|
    t.integer "facebook_id", :limit => 8
    t.string  "name",                     :null => false
    t.text    "thumb"
  end

  add_index "interests", ["facebook_id"], :name => "index_interests_on_facebook_id"

  create_table "messages", :force => true do |t|
    t.integer  "sender_id",   :null => false
    t.integer  "receiver_id", :null => false
    t.string   "status"
    t.text     "body"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  add_index "messages", ["sender_id", "receiver_id"], :name => "index_messages_on_sender_id_and_receiver_id"
  add_index "messages", ["status"], :name => "index_messages_on_status"

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
    t.string   "name"
    t.string   "phone_number"
    t.string   "address"
    t.float    "latitude"
    t.float    "longitude"
    t.text     "details"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  add_index "places", ["address"], :name => "index_places_on_address", :unique => true

  create_table "regions", :force => true do |t|
    t.string "name"
    t.text   "points"
    t.text   "text"
    t.float  "lat_min"
    t.float  "lat_max"
    t.float  "lng_min"
    t.float  "lng_max"
  end

  create_table "smile_game_choices", :force => true do |t|
    t.string   "status"
    t.integer  "smile_game_id"
    t.integer  "user_id"
    t.datetime "created_at"
    t.datetime "updated_at"
    t.integer  "position"
  end

  add_index "smile_game_choices", ["smile_game_id"], :name => "index_smile_game_choices_on_smile_game_id"

  create_table "smile_games", :force => true do |t|
    t.string   "status"
    t.integer  "sender_id"
    t.integer  "receiver_id"
    t.integer  "origin_id"
    t.datetime "created_at"
    t.datetime "updated_at"
    t.integer  "match_id"
  end

  add_index "smile_games", ["receiver_id"], :name => "index_smile_games_on_receiver_id"
  add_index "smile_games", ["sender_id"], :name => "index_smile_games_on_sender_id"
  add_index "smile_games", ["status"], :name => "index_smile_games_on_status"

  create_table "taggings", :force => true do |t|
    t.integer  "tag_id"
    t.integer  "taggable_id"
    t.string   "taggable_type"
    t.integer  "tagger_id"
    t.string   "tagger_type"
    t.string   "context",       :limit => 128
    t.datetime "created_at"
  end

  add_index "taggings", ["tag_id"], :name => "index_taggings_on_tag_id"
  add_index "taggings", ["taggable_id", "taggable_type", "context"], :name => "index_taggings_on_taggable_id_and_taggable_type_and_context"

  create_table "tags", :force => true do |t|
    t.string "name"
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
    t.string   "relationship_status"
    t.string   "interested_in"
    t.string   "work"
    t.integer  "photo_id"
    t.float    "latitude"
    t.float    "longitude"
    t.integer  "current_region_id"
    t.string   "status"
    t.text     "iphone_push_token"
  end

  add_index "users", ["status"], :name => "index_users_on_status"

end
