class SplitNameIntoFirstAndLast < ActiveRecord::Migration
  class Student < ActiveRecord::Base
    attr_accessible :name, :first_name, :last_name
  end

  def self.up
    add_column :students, :first_name, :string
    add_column :students, :last_name, :string
    
    add_index :students, :first_name
    add_index :students, :last_name

    populate_first_name_last_name_fields
  end

  def self.down
    remove_column :students, :first_name
    remove_column :students, :last_name
  end

  def self.populate_first_name_last_name_fields
    total = Student.all.length
    progress = 0
    Student.all.each do |s|
      s.update_attributes! :first_name => first_name(s.name), :last_name => last_name(s.name)
      progress += 1
      puts "#{progress}/#{total} Split #{s.name}"
    end
  end

  def self.first_name(full_name)
    parts = full_name.split /\s*,\s*/
    return parts.last.strip
  end

  def self.last_name(full_name)
    parts = full_name.split /\s*,\s*/
    return parts.first.strip
  end

end
