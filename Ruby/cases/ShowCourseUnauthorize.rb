class ShowCourseUnauthorize < BaseCase

  def go_test driver
    puts "Начало теста: просмотр страницы курса неавторизованным посетителем"
    BaseCase.unauthorized driver

    driver.navigate.to DOMAIN_NAME+'courses/31'

    error = []

    begin
      driver.find_element(:class, 'begin-learning')
      puts "   Успешно!"
    rescue
      puts "Произошла ошибка. Детали в логах"
      error = ['Страница курса для неавторизованного пользователя отображается некорректно.']
    end

    return error
  end
end
