class ShowIndex
  def go_test driver
    error = []
    puts "Начало теста: просмотр главной страницы неавторизованным посетителем"

    BaseCase.unauthorized driver

    driver.navigate.to DOMAIN_NAME

    begin
      driver.find_element(:class, 'choose-course')
      puts "   Успешно!"
    rescue
      puts "Произошла ошибка. Детали в логах"
      error = ['На главной странице не найден блок с курсами']
    end

    return error
  end
end