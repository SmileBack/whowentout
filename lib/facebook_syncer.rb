module FacebookSyncer

  def require_syncer(name)
    require Rails.root.join('lib', 'facebook_syncer', "#{name}_syncer")
  end

  def get_syncer_class(name)
    name = name.to_s
    require_syncer(name)
    FacebookSyncer.const_get(name.camelize + 'Syncer')
  end

  def sync(name)
    syncer_class = get_syncer_class(name)
    syncer = syncer_class.new
    syncer.sync(self, facebook_token)
  end

end