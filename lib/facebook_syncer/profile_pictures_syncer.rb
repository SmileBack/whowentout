module FacebookSyncer

  class ProfilePicturesSyncer

    def sync(user, facebook_token)
      hash = get_hash(facebook_token)
      update_user_from_hash(user, hash)
      user.save
    end

    def update_user_from_hash(user, hash)
      user.transaction do
        user.photos.destroy_all

        hash.each do |picture_data|
          user.photos.create(
            :facebook_id => picture_data['pid'],
            :created_at => Time.at(picture_data['created']),
            :thumb => picture_data['src_small'],
            :large => picture_data['src_big']
          )
        end

        user.photo = user.photos.first
      end
    end

    def get_hash(token)
      api = Koala::Facebook::API.new(token)
      api.fql_query("SELECT pid, created, src, src_small, src_big
                        FROM photo WHERE
                          aid IN (SELECT aid FROM album WHERE owner = me() AND type = 'profile')")
    end

  end

end