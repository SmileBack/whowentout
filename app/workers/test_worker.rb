class TestWorker
  @queue = :test_queue

  def self.perform(name)
    suffix = rand(1000..9999)
    (1..5).each do |n|
      save path("#{suffix}_#{n}"), "Time is now #{Time.now}, #{name}."
    end
  end

  def self.save(file_path, contents)
    File.open(file_path, 'w') { |f| f.write contents }
    sleep(4)
  end

  def self.path(n)
    File.join(File.dirname(__FILE__), "test_#{n}.txt" )
  end

end