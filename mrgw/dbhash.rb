require 'sqlite3'
require 'yaml'

class DbHash
  
  def initialize( path )
    @db = SQLite3::Database.new(path)
    @db.results_as_hash = true
    
    create_table unless table_exists?
  end
  
  def [](k)
    rows = @db.execute("SELECT value FROM data WHERE key = ?", k)
    return deserialize_value rows.first['value']
  end
  
  def []=(k, v)
    store(k, v)
  end
  
  def store(k, v)
    v = serialize_value v
    @db.execute("INSERT OR REPLACE INTO data (key, value) VALUES (?, ?)", k, v)
  end
  
  def delete(k)
    @db.execute("DELETE FROM data WHERE key = ?", k)
  end
  
  def each
    data = []
    @db.execute("SELECT key, value FROM data") do |row|
      row['value'] = deserialize_value row['value']
      
      yield row['key'], row['value'] if block_given?
      data << {:key => row['key'], :value => row['value']} if not block_given?
      
    end
    return data if not block_given?
  end
  
  def values
    values = []
    @db.execute("SELECT value FROM data") do |row|
      row['value'] = deserialize_value row['value']
      values << row['value']
    end
    return values
  end
  
  def clear
    @db.execute("DELETE FROM data")
  end
  
  def length
    rows = @db.execute("SELECT COUNT(*) AS count FROM data")
    puts rows.first['count']
  end
  
  def empty?
    length == 0
  end
  
  def has_key?(k)
    rows = @db.execute("SELECT COUNT(*) AS count FROM data WHERE key = ?", k)
    return rows.first['count'] > 0
  end
  
  def include?(k)
    has_key?(k)
  end
  
  private
  def serialize_value(value)
    YAML::dump(value)
  end
  
  def deserialize_value(value)
    YAML::load(value)
  end
  
  def table_exists?
    rows = @db.execute("SELECT COUNT(*) AS count FROM sqlite_master WHERE name=?", 'data')
    return rows.first['count'] > 0
  end
  
  def create_table
    sql = "
           CREATE TABLE data
            (
              id INTEGER PRIMARY KEY,
              key TEXT,
              value TEXT,
              UNIQUE(key)
            )
          "
    @db.execute(sql)
  end
  
end
