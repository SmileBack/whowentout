class String

  def last_name
    if include?(',')
      return strip.split(/\s*,\s*/).first
    else
      return strip.split(/\s+/).last
    end
  end

  def first_name
    if include?(',')
      return strip.split(/\s*,\s*/).last
    else
      return strip.split(/\s+/).first
    end
  end

end
