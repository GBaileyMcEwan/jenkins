pipeline {
	agent any
	stages {
		stage('Build') {
			steps {
				echo 'Running deployment'
				archiveArtifacts artifacts: 'src/index.html'
			}
		}
		stage('DeployToStaging') {
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
								configName: 'StagingServer',
								sshCredentials: [
									username: "$USERNAME",
									encryptedPassphrase: "$USERPASS"
								],
								transfers: [
									sshTransfer(
										sourceFiles: 'src/index.html && src/grabInput.php',
										removePrefix: 'src/',
										remoteDirectory: '/'
									)
								]
							)
						]
					)
				}
			}
		}
		stage('DeployToProd') {
			when {
				branch 'master'
			}
			steps {
				input 'Does the staging environment look OK?'
				milestone(1)
				withCredentials([usernamePassword(credentialsId: 'webserver_login', usernameVariable: 'USERNAME', passwordVariable: 'USERPASS')]) {
					sshPublisher(
						failOnError: true,
						continueOnError: false,
						publishers: [
							sshPublisherDesc(
								configName: 'ProductionServer',
								sshCredentials: [
									username: "$USERNAME",
									encryptedPassphrase: "$USERPASS"
								],
								transfers: [
									sshTransfer(
										sourceFiles: 'src/index.html && src/grabInput.php',
										removePrefix: 'src/',
										remoteDirectory: '/'
									)
								]
							)
						]
					)
				}
			}
		}
		stage('BasicF5ConfigThroughAnsible') {
			when {
				branch 'master'
			}
			steps {
				input 'Deploy to F5 (Infrastructure As Code)?'
				milestone(2)
				withCredentials([usernamePassword(credentialsId: 'webserver_login', usernameVariable: 'USERNAME', passwordVariable: 'USERPASS')]) {
					sshPublisher(
						failOnError: true,
						continueOnError: false,
						publishers: [
							sshPublisherDesc(
								configName: 'AnsibleServer',
								sshCredentials: [
									username: "$USERNAME",
									encryptedPassphrase: "$USERPASS"
								],
								transfers: [
									sshTransfer(
										sourceFiles: 'ansible/configureF5.yaml',
										removePrefix: 'ansible/',
										remoteDirectory: '/',
										execCommand: 'ansible-playbook configureF5.yaml'
									)
								]
							)
						]
					)
				}
			}
		}
		stage('F5WAFConfigThroughAnsible') {
			when {
				branch 'master'
			}
			steps {
				input 'Deploy WAF??'
				milestone(3)
				withCredentials([usernamePassword(credentialsId: 'webserver_login', usernameVariable: 'USERNAME', passwordVariable: 'USERPASS')]) {
					sshPublisher(
						failOnError: true,
						continueOnError: false,
						publishers: [
							sshPublisherDesc(
								configName: 'AnsibleServer',
								sshCredentials: [
									username: "$USERNAME",
									encryptedPassphrase: "$USERPASS"
								],
								transfers: [
									sshTransfer(
										sourceFiles: 'ansible/configureWAF.yaml',
										removePrefix: 'ansible/',
										remoteDirectory: '/',
										execCommand: 'ansible-playbook configureWAF.yaml'
									)
								]
							)
						]
					)
				}
			}
		}
		stage('RollbackConfig') {
			when {
				branch 'master'
			}
			steps {
				input 'Rollback F5 & Webserver Config?'
				milestone(4)
				withCredentials([usernamePassword(credentialsId: 'webserver_login', usernameVariable: 'USERNAME', passwordVariable: 'USERPASS')]) {
					sshPublisher(
						failOnError: true,
						continueOnError: false,
						publishers: [
							sshPublisherDesc(
								configName: 'AnsibleServer',
								sshCredentials: [
									username: "$USERNAME",
									encryptedPassphrase: "$USERPASS"
								],
								transfers: [
									sshTransfer(
										sourceFiles: 'ansible/rollbackF5.yaml',
										removePrefix: 'ansible/',
										remoteDirectory: '/',
										execCommand: 'ansible-playbook rollbackF5.yaml && rm configureF5.yaml rollbackF5.yaml configureWAF.yaml && rm /var/www/html/jenkins/index.html /var/www/html/jenkins-staging/index.html /var/www/html/jenkins/grabInput.php /var/www/html/jenkins-staging/grabInput.php && cp /var/www/html/index.nginx-debian.html /var/www/html/jenkins/index.html && cp /var/www/html/index.nginx-debian-staging.html /var/www/html/jenkins-staging/index.html'
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
