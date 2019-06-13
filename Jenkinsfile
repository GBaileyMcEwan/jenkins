pipeline {
	agent any
	stages {
		stage('Build') {
			steps {
				echo 'Running deployment'
				archiveArtifacts artifacts: 'src/index.html'
			}
		}
		stage('Deploy') {
			when {
				branch 'master'
			}
			steps {
				withCredentials([usernamePassword(credentialsId: 'webserver_login', usernameVariable: 'USERNAME', passwordVariable: 'USERPASS')]) {
					sshPublisher(
						failOnError: true,
						continueOnError: false,
						publishers: [
							sshPublisherDesc(
								configName: 'UbuntuBox',
								sshCredentials: [
									username: "$USERNAME",
									encryptedPassphrase: "$USERPASS"
								],
								transfers: [
									sshTransfer(
										sourceFiles: 'src/index.html',
										removePrefix: 'src/',
										remoteDirectory: '/var/www/html/jenkins'
									)
								]
							)
						]
					)
				}
			}
		}
	}
}
