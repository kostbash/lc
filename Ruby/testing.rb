require 'selenium-webdriver'
require __dir__+'/BaseCase'

if ARGV[0].nil?
  CASE_NAME   = :all
else
CASE_NAME     = ARGV[0]
end

if ARGV[1].nil?
  DOMAIN_NAME   = 'http://cursys/'
else
  DOMAIN_NAME     = ARGV[1]
end


CASES_DIR     = __dir__+'/cases/'

USER_NAME     = 'JorryGo'
USER_PASSWORD = "111"

errors = []

puts "Начало полного тестирования."
print "Запуск браузера..."

driver = Selenium::WebDriver.for :firefox
driver.manage.window.maximize
driver.manage.timeouts.implicit_wait = 1 # seconds
driver.navigate.to DOMAIN_NAME

puts "   Успешно!"

if CASE_NAME == :all
  testCases = Dir.entries(CASES_DIR).select {|file|
    next if File.directory? file
    file = File.basename(file, ".rb")

    require CASES_DIR + file
    object = Object.const_get(file).new

    errors += object.go_test driver


  }
else
  require CASES_DIR+CASE_NAME
  object = Object.const_get(CASE_NAME).new

  errors += object.go_test driver
end


# require CASES_DIR+'RegisterParent'
#
# lol = RegisterParent.new
# error = lol.go_test driver
#
# puts error.to_s.encode("UTF-8")


unless errors[0].nil?
  File.open("errors.txt", 'w') {|f|
    errors.each { |elem|
      f.write(elem+"\r\n")
    }
  }
end










#driver.quit


