if @user.nil?
  json.success false
  json.error "User is not logged in"
else
  json.(@user, id, facebook_id, first_name, last_name)
end
