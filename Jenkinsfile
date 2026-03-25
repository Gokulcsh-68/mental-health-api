pipeline {
    agent any
    environment {
        IMAGE_NAME = "registry.cureselecthealthcare.com/mentalhealth-uat"
        CONTAINER  = "mentalhealth"
        PORT       = "8201"
        NETWORK    = "mongo-network"
    }
    stages {
        stage('Checkout') {
            steps { checkout scm }
        }
        stage('Install & Test') {
            steps {
                sh 'npm ci'
                sh 'npm test --if-present'
            }
        }
        stage('Build image') {
            steps {
                sh 'docker build -t ${IMAGE_NAME}:${BUILD_NUMBER} -t ${IMAGE_NAME}:latest .'
            }
        }
        stage('Push to registry') {
            steps {
                sh 'docker push ${IMAGE_NAME}:${BUILD_NUMBER}'
                sh 'docker push ${IMAGE_NAME}:latest'
            }
        }
        stage('Deploy') {
            steps {
                sh '''
                    docker stop ${CONTAINER} || true
                    docker rm   ${CONTAINER} || true
                    docker pull ${IMAGE_NAME}:latest
                    docker run -d \
                        --name ${CONTAINER} \
                        --network ${NETWORK} \
                        --restart unless-stopped \
                        -p ${PORT}:3000 \
                        -e NODE_ENV=production \
                        -e PORT=3000 \
                        -e DB_HOST=MYSQL \
                        -e DB_PORT=3306 \
                        -e "DB-NAME=mentalhealth" \
                        -e DB_USER=mentalhealth_user \
                        -e DB_PASS=MentalHealth@123 \
                        -e "MONGO_URI=mongodb://root:n1Gf31BHL7Uk@mongo:27017/mentalhealth?authSource=admin" \
                        -e JWT_SECRET=e2127790e1bd5b187a087440355ed26a353459fe1abfe6fad54e5b1f2cbf26b43f149903976cb150ccfdc5a0d9163ccad8508073125eb059b7020b43c2a21260 \
                        -e JWT_EXPIRE=30d \
                        -e JWT_COOKIE_EXPIRE=30 \
                        -e "API_KEY=ygXk1R15vil+RD9Ix5c4cUPqND5i7+M3NRsEmxByDL8=" \
                        -e CORS_ORIGIN=* \
                        -e CURESELECT_API_ENDPOINT=https://services-api.a2zhealth.in/ \
                        -e CURESELECT_API_CLIENT_ID=televet-v3-staging \
                        -e CURESELECT_API_CLIENT_SECRET=83fef8ec35f37968a9b684a5c400a54a \
                        -e CURESELECT_CATEGORY_ID=2 \
                        -e OPENAI_API_MODEL=gpt-4o \
                        -e S3_KEY=AKIA3GRU7BB33WCCTN4V \
                        -e S3_REGION=ap-south-1 \
                        -e S3_BUCKET=a2zschoolfolio \
                        -e S3_BASE_PATH=temp/ \
                        -e S3_PUBLIC_BASE_PATH=https://a2zschoolfolio.s3.ap-south-1.amazonaws.com/ \
                        ${IMAGE_NAME}:latest
                    docker network connect service-network ${CONTAINER} || true
                '''
            }
        }
    }
    post {
        success { echo "Mental Health API deployed at http://192.168.100.104:${PORT}" }
        failure {
            echo "Deployment failed! Check Jenkins logs."
        }
        always { sh 'docker image prune -f' }
    }
}