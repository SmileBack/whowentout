require 'yaml'

class ObjectCache
  
  def initialize(path)
    self.path = path
  end
  
  def path
    @path
  end
  def path=(val)
    @path = val
  end

  def each
    objects = []
    Dir["#{@path}/*.yml"].each do |f|
      obj = YAML::load(open(f))
      if block_given?
        yield obj
      else
        objects << obj
      end
    end
    return objects if !block_given?
  end

  def [](name)
    path = filepath(name)
    if File.exist?(path)
      return YAML::load(open(path))
    end
  end

  def []=(name, object)
    path = filepath(name)
    File.open(path, 'w') do |f|
      f.write(YAML::dump object)
    end
  end

  private
  def filepath(name)
    "#{@path}/#{name}.yml"
  end

end
