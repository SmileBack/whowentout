class CreateCategoryAndPictureInterestsColumn < ActiveRecord::Migration
  def change
    add_column :interests, :thumb, :text
  end
end
