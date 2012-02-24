require 'fb_graph'

require_relative '../database'
require_relative 'string_extensions'
require_relative 'facebook_id_directory'

class FacebookLinker

  def initialize(show_messages=false)
    connect_to_database('gwu')
    @facebook_ids = FacebookIdDirectory.new
    @show_messages = show_messages
  end

  def already_linked?(facebook_id)
    return Student.exists?(:facebook_id => facebook_id)
  end

  def cross_link_user(name, fb_username)
    facebook_id = @facebook_ids[fb_username]
    if already_linked?(facebook_id)
      #puts "Already linked #{name}, #{fb_username}"
      return Student.where(:facebook_id => facebook_id).first
    end

    facebook_user = lookup_facebook_user(facebook_id)

    if facebook_user.nil?
      puts "#{name} NOT FOUND ON FACEBOOK" if @show_messages
      return
    end

    student = lookup_student(facebook_user.name)
    if student.nil?
      puts "NOT FOUND IN DIRECTORY #{name}" if @show_messages
      return
    end

    uid = facebook_user.identifier
    facebook_name = facebook_user.name
    facebook_gender = facebook_user.gender == 'female' ? 'F' : 'M'

    if @show_messages
      puts "----------------"
      puts "Provided Name = #{name}"
      puts "Facebook ID = #{uid}"
      puts "Facebook Name = #{facebook_name}"
      puts "Gender = #{facebook_gender}"
    end

    student.facebook_id = uid
    student.facebook_name = facebook_name
    student.gender = facebook_gender
    student.save

    return student
  end

  def lookup_facebook_user(facebook_id)
    begin
      FbGraph::User.fetch(facebook_id)
    rescue FbGraph::NotFound => e
      puts "#{facebook_id} not found"
    rescue FbGraph::Unauthorized => e
      puts "#{facebook_id} unauthorized"
    rescue FbGraph::Exception => e
      puts "#{facebook_id} facebook graph error"
    rescue URI::InvalidURIError => e
      puts "#{facebook_id} invalid uri"
    end
  end

  def lookup_student(full_name)
    first_name = full_name.first_name
    last_name = full_name.last_name
    patterns = [
        "#{last_name}, #{first_name}",
        "#{last_name}%, #{first_name[0, 1]}%",
        "#{last_name}%, #{first_name[0, 2]}%",
        "#{last_name}%, #{first_name[0, 3]}%",
        "#{last_name}%, #{first_name[0, 4]}%",
        "#{last_name}, %",
        "%, #{first_name}"
    ]
    patterns.each do |pat|
      matches = Student.where('name LIKE ?', pat)
      return matches.first if matches.length == 1
    end

    return nil
  end
end
