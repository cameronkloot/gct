up:
	docker-compose up --build -d

stop:
	docker-compose stop

down:
	docker-compose down

drma:
	docker-compose down --volumes --rmi all
