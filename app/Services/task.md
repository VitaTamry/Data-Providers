We have three providers that make transfers using a phone number,
You have examples of the structure of the data sent,
We need to read and make some filter operations on them to get the result.

DataProviderW data is stored in [DataProviderW.json]
DataProviderX data is stored in [DataProviderX.json]
DataProviderY data is stored in [DataProviderY.json]

* DataProviderW schema is
{
  amount:500.00,
  currency:'EGP',
  phone: 00201134567890,
  status: 'done',
  created_at: '2021-03-29 09:36:11',
  id: 12345678
}
We have three status for DataProviderW
paid which will have status done
pending which will have status wait
reject which will have status nope

* DataProviderX schema is
{
  transactionAmount:200,
  Currency:'USD',
  senderPhone:00201234567890,
  transactionStatus:1,
  transactionDate: '2021-03-29 09:36:11',
  transactionIdentification: 'd3d29d70-1d25-11e3-8591-034165a3a613'
}
we have three status for DataProviderX
paid which will have status 1
pending which will have status 2
reject which will have status 3

* DataProviderY schema is
{
  amount:300,
  currency:'EGP',
  phone: 00201034567890,
  status:100,
  created_at: '2021-03-29 09:36:11',
  id: '4fc2-a8d1'
}
we have three status for DataProviderY
paid which will have status 100
pending which will have status 200
reject which will have status 300


* Acceptance Criteria: 
Using PHP Lumen (Micro-Framework By Laravel), implement this API endpoint /api/v1/transactaions.

- It should list all transactaions which combine transactaions from all the available providerDataProvider(W/X/Y).
- It should be able to filter result by providers for example /api/v1/transactaions?provider=DataProviderX it should return transactaions from DataProviderX.
- It should be able to filter result three statusCode (paid, pending, reject) for example /api/v1/transactaions?statusCode=paid .
- It should return all transactaions from all providers that have status code paid.
- It should be able to filer by amount range for example /api/v1/transactaions?amounteMin=10&amounteMax=100 it should return result between 10 and 100 including 10 and 100.
- It should be able to filer by currency.
- It should be able to combine all this filter together.

* The Evaluation
Task will be evaluated based on

- Code quality.
- Application performance in reading large files.
- Code scalability : ability to add DataProviderZ by small changes.
- Unit tests coverage.
- Docker, is plus.