class User < ActiveRecord::Base

  validates_inclusion_of :gender, :in => ['M', 'F']

  def self.find_by_token(token)
    profile = get_profile_hash(token)

    facebook_id = profile['id'].to_i
    user = User.find_by_facebook_id(facebook_id)

    if user.nil?
      user = User.new

      user.gender = profile['gender'][0].upcase
      user.facebook_id = profile['id'].to_i
      user.first_name = profile['first_name']
      user.last_name = profile['last_name']
      user.email = profile['email']
      user.birthday = Date.strptime(profile['birthday'], "%m/%d/%Y")

      user.save
    end

    return user
  end

  private

    def self.get_profile_hash(token)
      @profile_hash_store ||= Hash.new do |h, k|
        api = Koala::Facebook::API.new(token)
        h[k] = api.get_object('me')
      end
      return @profile_hash_store[token]
    end

end