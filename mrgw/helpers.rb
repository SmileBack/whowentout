def four_subsets(str)
  subsets = []
  str.downcase.split(/[^a-z]+/).each do |segment|
    for i in 0..segment.length - 4
      subsets << segment[i, 4]
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
