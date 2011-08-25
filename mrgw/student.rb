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
  

  def to_s
    "#{name} -- #{email}"
  end
  
end
