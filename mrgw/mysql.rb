require 'rubygems'
require 'mysql2'

my = Mysql.real_connect('localhost', 'root', '123', 'whowentout')
#my.query("SELECT first_name, last_name, FROM users").each do |fname, lname|
#  p fname, lname
#end
