require 'selenium-webdriver'

DOMAIN_NAME   = 'http://cursys'
USER_LOGIN    = 'JorryGo'
USER_PASSWORD = '111'
DIR_BLOCKS    = 'blocks/'

is_working = true

puts "Начало полного тестирования."
print "Запуск браузера..."

driver = Selenium::WebDriver.for :firefox
driver.manage.window.maximize
driver.manage.timeouts.implicit_wait = 1 # seconds
driver.navigate.to DOMAIN_NAME

puts "   Успешно!"


is_logined = driver.find_element(:link_text, 'Войдите')
if is_logined
  print "Авторизовываемся..."
  is_logined.click
  driver.find_element(:name, 'LoginForm[username]').send_keys  USER_LOGIN
  driver.find_element(:name, 'LoginForm[password]').send_keys  USER_PASSWORD
  driver.find_element(:name, 'LoginForm[password]').submit
  begin
    driver.find_element(:id, 'logout')
    puts "   Успешно!"
  rescue
    puts "Провал!"
    puts "Ошибка авторизации! Проверьте правильность логина и пароля. Дальнейшее проведение тестов невозможно."
    is_working = false
    driver.quit

  end
end

if is_working

  driver.navigate.to DOMAIN_NAME + ""


  # puts "Заверщение работы"
  # driver.quit
end






