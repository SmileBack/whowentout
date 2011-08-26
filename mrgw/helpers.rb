def string_subsets(str, len)
  subsets = []
  str.downcase.split(/[^a-z]+/).each do |segment|
    next if segment.length > len
    
    for i in 0..segment.length - len
      subsets << segment[i, len]
    end
  end
  return subsets.uniq
end

def student_subsets(student)
  subsets = four_subsets(student.name)
  return subsets.uniq
end

def range(length)
  ('a' * length .. 'z' * length)
end
