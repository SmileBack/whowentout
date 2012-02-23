require 'fb_graph'

require_relative '../database'
require_relative 'facebook_id_directory'

class FacebookLinker

  def initialize
    connect_to_database('gwu')
    @facebook_ids = FacebookIdDirectory.new
  end

  def already_linked(fb_username)
    facebook_id = @facebook_ids[fb_username]
    return Student.exists?(:facebook_id => facebook_id)
  end

  def cross_link_user(name, fb_username)
    if already_linked(fb_username)
      #puts "Already linked #{name}, #{fb_username}"
      return
    end

    facebook_id = @facebook_ids[fb_username]
    facebook_user = lookup_facebook_user(facebook_id)

    if facebook_user.nil?
      puts "#{name} NOT FOUND ON FACEBOOK"
      return
    end

    student = lookup_student(facebook_user.name)
    if student.nil?
      puts "NOT FOUND IN DIRECTORY #{name}"
      return
    end

    uid = facebook_user.identifier
    facebook_name = facebook_user.name
    facebook_gender = facebook_user.gender == 'female' ? 'F' : 'M'

    puts "----------------"
    puts "Provided Name = #{name}"
    puts "Facebook ID = #{uid}"
    puts "Facebook Name = #{facebook_name}"
    puts "Gender = #{facebook_gender}"

    student.facebook_id = uid
    student.facebook_name = facebook_name
    student.gender = facebook_gender
    student.save

    puts "Directory Name = #{student.name}"
    puts "Directory Email = #{student.email}"
  end

  def lookup_facebook_user(facebook_id)
    begin
      FbGraph::User.fetch(facebook_id)
    rescue FbGraph::NotFound => e
      puts "#{facebook_id} not found"
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

class String

  def last_name
    if include?(',')
      return split(/\s*,\s*/).first
    else
      return split(/\s+/).last
    end
  end

  def first_name
    if include?(',')
      return split(/\s*,\s*/).last
    else
      return split(/\s+/).first
    end
  end

end
