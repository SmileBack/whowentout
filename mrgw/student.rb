require './objectcache'

class Student
  
  def initialize(data = {})
    self.data = data
  end

  def data
    @data
  end
  def data=(val)
    @data = val
  end

  def name
    data['name']
  end
  def name=(val)
    data['name'] = val
  end

  def email
    data['email']
  end
  def email=(val)
    data['email'] = val
  end
  
  def filename
    if email != nil && email != ""
      "email/" + email.split("@").first + '.yml'
    else
      "noemail/#{name}.yml"
    end
  end

  def to_s
    "#{name} -- #{email}"
  end

  def save
    if email != nil && email != ""
      c = ObjectCache.new "data/email"
    else
      c = ObjectCache.new "data/noemail"
    end
    c[email] = self
  end

end
